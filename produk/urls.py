from django.urls import path
from . import views

urlpatterns = [
    path('', views.produk_list, name='produk_list'),
    path('fetch/', views.fetch_produk, name='fetch_produk'),
    path('tambah/', views.produk_create, name='produk_create'),
    path('edit/<int:pk>/', views.produk_edit, name='produk_edit'),
    path('hapus/<int:pk>/', views.produk_delete, name='produk_delete'),
    path('hapus-semua/', views.produk_delete_all, name='produk_delete_all'),
]