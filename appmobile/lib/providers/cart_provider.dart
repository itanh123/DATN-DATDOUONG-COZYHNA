import 'package:flutter/material.dart';
import '../models/cart_item.dart';
import '../models/product.dart';
import '../models/topping.dart';

class CartProvider with ChangeNotifier {
  List<CartItem> _items = [];

  List<CartItem> get items => _items;

  double get totalAmount {
    double total = 0.0;
    for (var item in _items) {
      total += item.lineTotal;
    }
    return total;
  }

  int get itemCount {
    int count = 0;
    for (var item in _items) {
      count += item.quantity;
    }
    return count;
  }

  void addItem(Product product, ProductSize size, List<CartItemTopping> toppings, int quantity) {
    // Check if same product, same size, same toppings exist
    int index = _items.indexWhere((item) {
      if (item.product.id != product.id) return false;
      if (item.selectedSize.id != size.id) return false;
      
      // So sánh Toppings
      if (item.toppings.length != toppings.length) return false;
      
      // Lấy danh sách ID topping của item hiện tại và item mới thêm
      List<int> currentTopIds = item.toppings.map((e) => e.topping.id).toList()..sort();
      List<int> newTopIds = toppings.map((e) => e.topping.id).toList()..sort();
      
      for(int i = 0; i < currentTopIds.length; i++){
        if(currentTopIds[i] != newTopIds[i]) return false;
      }
      return true;
    });

    if (index >= 0) {
      _items[index].quantity += quantity;
    } else {
      _items.add(CartItem(
        product: product,
        selectedSize: size,
        toppings: toppings,
        quantity: quantity,
      ));
    }
    notifyListeners();
  }

  void updateQuantity(CartItem item, int delta) {
    int index = _items.indexOf(item);
    if (index >= 0) {
      _items[index].quantity += delta;
      if (_items[index].quantity <= 0) {
        _items.removeAt(index);
      }
      notifyListeners();
    }
  }

  void clear() {
    _items.clear();
    notifyListeners();
  }
}
