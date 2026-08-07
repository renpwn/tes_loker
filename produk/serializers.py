from rest_framework import serializers
from .models import Produk

class ProdukSerializer(serializers.ModelSerializer):
    class Meta:
        model = Produk
        fields = '__all__'

    def validate_nama_produk(self, value):
        if not value:
            raise serializers.ValidationError("Nama produk wajib diisi")
        return value

    def validate_harga(self, value):
        if value < 0:
            raise serializers.ValidationError("Harga harus angka")
        return value