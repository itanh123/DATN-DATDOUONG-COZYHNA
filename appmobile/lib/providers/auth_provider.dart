import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;
  bool _isLoading = false;

  User? get user => _user;
  String? get token => _token;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _token != null;

  Future<void> checkLoginStatus() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
    
    if (_token != null) {
      // Gọi API lấy thông tin user để đảm bảo token còn hạn
      try {
        final response = await ApiService.get('/me', auth: true);
        if (response.statusCode == 200) {
          final data = json.decode(response.body);
          _user = User.fromJson(data['data']);
        } else {
          _token = null;
          await prefs.remove('auth_token');
        }
      } catch (e) {
        _token = null;
      }
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.post('/login', {
        'email': email,
        'password': password,
      });

      final data = json.decode(response.body);

      if (response.statusCode == 200 && data['success']) {
        _token = data['data']['token'];
        _user = User.fromJson(data['data']['user']);

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);

        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> googleLogin(String email, String googleId, String name) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.post('/auth/google', {
        'email': email,
        'google_id': googleId,
        'name': name,
      });

      final data = json.decode(response.body);

      if (response.statusCode == 200 && data['success']) {
        _token = data['data']['token'];
        _user = User.fromJson(data['data']['user']);

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);

        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await ApiService.post('/logout', {}, auth: true);
    _token = null;
    _user = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    notifyListeners();
  }
}
