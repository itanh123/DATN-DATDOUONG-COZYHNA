import 'package:flutter_application_1/services/api_service.dart';

class ProductSize {
  final int id;
  final int productId;
  final int? sizeId;
  final double sellingPrice;
  final int stock;

  ProductSize({
    required this.id,
    required this.productId,
    this.sizeId,
    required this.sellingPrice,
    required this.stock,
  });

  factory ProductSize.fromJson(Map<String, dynamic> json) {
    return ProductSize(
      id: json['id'],
      productId: json['product_id'],
      sizeId: json['size_id'],
      sellingPrice: double.tryParse(json['selling_price'].toString()) ?? 0.0,
      stock: int.tryParse(json['stock']?.toString() ?? '0') ?? 0,
    );
  }
}

class Product {
  final int id;
  final String name;
  final int categoryId;
  final String? image;
  final String? description;
  final int stock;
  final List<ProductSize> sizes;

  Product({
    required this.id,
    required this.name,
    required this.categoryId,
    this.image,
    this.description,
    required this.stock,
    required this.sizes,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    var sizeList = json['product_sizes'] as List? ?? [];
    List<ProductSize> sizes = sizeList.map((i) => ProductSize.fromJson(i)).toList();

    return Product(
      id: json['id'],
      name: json['name'],
      categoryId: json['category_id'],
      image: json['image'],
      description: json['description'],
      stock: int.tryParse(json['stock']?.toString() ?? '0') ?? 0,
      sizes: sizes,
    );
  }

  double get minPrice {
    if (sizes.isEmpty) return 0.0;
    double min = sizes[0].sellingPrice;
    for (var size in sizes) {
      if (size.sellingPrice < min) min = size.sellingPrice;
    }
    return min;
  }

  String get imageUrl {
    if (image == null || image!.isEmpty) {
      return 'https://via.placeholder.com/400';
    }
    
    final baseUrl = ApiService.baseUrl.replaceAll('/api', '');
    
    // Database lưu dạng /storage/products/abc.jpg
    if (image!.startsWith('/')) {
      return '$baseUrl$image';
    } else {
      return '$baseUrl/$image';
    }
  }
}
