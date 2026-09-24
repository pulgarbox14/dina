from fastapi import FastAPI, APIRouter, HTTPException
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
from pathlib import Path
from pydantic import BaseModel, Field, ConfigDict, EmailStr
from typing import List, Optional
import uuid
from datetime import datetime, timezone

from seed_data import PRODUCTS

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ['DB_NAME']]

app = FastAPI(title="Perlae Atelier API")
api_router = APIRouter(prefix="/api")

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)


def now_iso():
    return datetime.now(timezone.utc).isoformat()


class Product(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str
    name: str
    subtitle: str
    price: int
    category: str
    accent: str
    tag: Optional[str] = None
    weaving_hours: int
    dimensions: str
    images: List[str]
    description: str
    featured: bool = False


class OrderItem(BaseModel):
    product_id: str
    name: str
    price: int
    quantity: int = Field(ge=1)


class OrderCreate(BaseModel):
    customer_name: str = Field(min_length=2)
    phone: str = Field(min_length=6)
    email: Optional[str] = None
    address: str = Field(min_length=3)
    note: Optional[str] = None
    items: List[OrderItem] = Field(min_length=1)


class Order(OrderCreate):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    total: int
    status: str = "nouvelle"
    created_at: str = Field(default_factory=now_iso)


class ContactCreate(BaseModel):
    name: str = Field(min_length=2)
    email: EmailStr
    subject: str = Field(min_length=2)
    message: str = Field(min_length=5)


class ContactMessage(ContactCreate):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    created_at: str = Field(default_factory=now_iso)


class NewsletterCreate(BaseModel):
    email: EmailStr


@app.on_event("startup")
async def seed_products():
    if await db.products.count_documents({}) == 0:
        await db.products.insert_many([p.copy() for p in PRODUCTS])
        logger.info("Seeded %d products", len(PRODUCTS))


@api_router.get("/")
async def root():
    return {"message": "Perlae Atelier API"}


@api_router.get("/products", response_model=List[Product])
async def list_products(category: Optional[str] = None, featured: Optional[bool] = None):
    query = {}
    if category and category != "tous":
        query["category"] = category
    if featured is not None:
        query["featured"] = featured
    docs = await db.products.find(query, {"_id": 0}).to_list(200)
    return docs


@api_router.get("/products/{product_id}", response_model=Product)
async def get_product(product_id: str):
    doc = await db.products.find_one({"id": product_id}, {"_id": 0})
    if not doc:
        raise HTTPException(status_code=404, detail="Produit introuvable")
    return doc


@api_router.post("/orders", response_model=Order)
async def create_order(payload: OrderCreate):
    total = sum(i.price * i.quantity for i in payload.items)
    order = Order(**payload.model_dump(), total=total)
    await db.orders.insert_one(order.model_dump())
    return order


@api_router.get("/orders", response_model=List[Order])
async def list_orders():
    return await db.orders.find({}, {"_id": 0}).sort("created_at", -1).to_list(200)


@api_router.post("/contact", response_model=ContactMessage)
async def create_contact(payload: ContactCreate):
    msg = ContactMessage(**payload.model_dump())
    await db.contact_messages.insert_one(msg.model_dump())
    return msg


@api_router.post("/newsletter")
async def subscribe_newsletter(payload: NewsletterCreate):
    existing = await db.newsletter.find_one({"email": payload.email})
    if not existing:
        await db.newsletter.insert_one({"email": payload.email, "created_at": now_iso()})
    return {"ok": True, "email": payload.email}


app.include_router(api_router)

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)


@app.on_event("shutdown")
async def shutdown_db_client():
    client.close()
