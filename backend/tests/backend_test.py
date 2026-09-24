"""Backend tests for Perlae Atelier API"""
import os
import requests
import pytest

BASE_URL = os.environ.get('REACT_APP_BACKEND_URL', 'https://landing-3d-shop.preview.emergentagent.com').rstrip('/')
API = f"{BASE_URL}/api"


# --- Root ---
class TestRoot:
    def test_root_message(self):
        r = requests.get(f"{API}/")
        assert r.status_code == 200
        assert r.json().get("message") == "Dina Perles API"


# --- Products ---
class TestProducts:
    def test_list_all(self):
        r = requests.get(f"{API}/products")
        assert r.status_code == 200
        data = r.json()
        assert isinstance(data, list)
        assert len(data) == 9, f"expected 9 seeded, got {len(data)}"

    def test_filter_bijoux(self):
        r = requests.get(f"{API}/products", params={"category": "bijoux"})
        assert r.status_code == 200
        data = r.json()
        assert len(data) == 3
        assert all(p["category"] == "bijoux" for p in data)

    def test_filter_tous(self):
        r = requests.get(f"{API}/products", params={"category": "tous"})
        assert r.status_code == 200
        assert len(r.json()) == 9

    def test_featured(self):
        r = requests.get(f"{API}/products", params={"featured": "true"})
        assert r.status_code == 200
        data = r.json()
        assert all(p["featured"] is True for p in data)
        assert len(data) >= 5

    def test_get_by_id(self):
        r = requests.get(f"{API}/products/sac-lune-nacre")
        assert r.status_code == 200
        data = r.json()
        assert data["id"] == "sac-lune-nacre"
        assert data["name"] == "Le Sac Lune Nacre"
        assert data["price"] == 55000

    def test_unknown_id_404(self):
        r = requests.get(f"{API}/products/nonexistent-xyz")
        assert r.status_code == 404


# --- Orders ---
class TestOrders:
    def test_create_order_valid(self):
        payload = {
            "customer_name": "TEST_Client",
            "phone": "+2250700000000",
            "address": "Cocody, Abidjan",
            "items": [
                {"product_id": "sac-lune-nacre", "name": "Le Sac Lune Nacre", "price": 55000, "quantity": 2},
                {"product_id": "parure-ambre", "name": "La Parure Ambre", "price": 45000, "quantity": 1},
            ],
        }
        r = requests.post(f"{API}/orders", json=payload)
        assert r.status_code == 200, r.text
        data = r.json()
        assert data["total"] == 55000 * 2 + 45000
        assert "id" in data and len(data["id"]) > 0
        assert data["status"] == "nouvelle"

    def test_create_order_empty_items(self):
        payload = {"customer_name": "T", "phone": "123456", "address": "abc", "items": []}
        r = requests.post(f"{API}/orders", json=payload)
        assert r.status_code == 422


# --- Contact ---
class TestContact:
    def test_contact_valid(self):
        r = requests.post(f"{API}/contact", json={
            "name": "TEST_User", "email": "test@example.com",
            "subject": "Test", "message": "Ceci est un message de test"
        })
        assert r.status_code == 200
        data = r.json()
        assert "id" in data
        assert data["email"] == "test@example.com"

    def test_contact_invalid_email(self):
        r = requests.post(f"{API}/contact", json={
            "name": "T", "email": "not-an-email", "subject": "s", "message": "hello!"
        })
        assert r.status_code == 422


# --- Newsletter ---
class TestNewsletter:
    def test_newsletter_valid(self):
        r = requests.post(f"{API}/newsletter", json={"email": "TEST_news@example.com"})
        assert r.status_code == 200
        assert r.json().get("ok") is True

    def test_newsletter_invalid(self):
        r = requests.post(f"{API}/newsletter", json={"email": "bad"})
        assert r.status_code == 422
