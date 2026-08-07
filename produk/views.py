from django.shortcuts import render, redirect, get_object_or_404
from django.http import HttpResponse

# Create your views here.
from .models import Produk
from .serializers import ProdukSerializer
from .services import fetch_and_store_produk

def produk_list(request):
    total = Produk.objects.count()
     # Ambil parameter filter dari query string
    status_filter = request.GET.get('status', 'bisa dijual')
    
    if(status_filter == 'all'):
      data = Produk.objects.all()
    else:
      data = Produk.objects.filter(status__nama_status=status_filter)
      
    return render(request, 'produk/list.html', {
      'produk': data,
      'status_filter': status_filter,
      'is_empty': total == 0
    })


def produk_create(request):
    if request.method == 'POST':
        data = request.POST.copy()
        if 'harga' in data:
          data['harga'] = data['harga'].replace('.', '').replace(',', '')
        
        serializer = ProdukSerializer(data=data)
        if serializer.is_valid():
            serializer.save()
            return redirect('produk_list')
    else:
        serializer = ProdukSerializer()
    return render(request, 'produk/form.html', {'serializer': serializer})


def produk_edit(request, pk):
    produk = get_object_or_404(Produk, pk=pk)
    data = request.POST.copy()
    if 'harga' in data:
      data['harga'] = data['harga'].replace('.', '').replace(',', '')
        
    serializer = ProdukSerializer(
        instance=produk,
        data=data or None
    )

    if request.method == 'POST':
        if serializer.is_valid():
            serializer.save()
            return redirect('produk_list')

    return render(request, 'produk/form.html', {
        'serializer': serializer
    })


def produk_delete(request, pk):
    get_object_or_404(Produk, pk=pk).delete()
    return redirect('produk_list')


def fetch_produk(request):
  try:
    fetch_and_store_produk()
    return redirect('produk_list')
  except Exception as e:
    return render(request, 'produk/error.html', {
      'message': str(e),
      'debug_username': getattr(e, 'username', None),
      'debug_password': getattr(e, 'password', None),
      'debug_password_md5': getattr(e, 'passwordmd5', None),
    })
    
    
def produk_delete_all(request):
    Produk.objects.all().delete()
    return redirect('produk_list')