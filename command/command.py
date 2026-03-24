# Strategy Pattern - Defines a family of algorithms and makes them interchangeable

from abc import ABC, abstractmethod


class PaymentStrategy(ABC):
    @abstractmethod
    def pay(self, amount):
        pass


class CreditCardStrategy(PaymentStrategy):
    def __init__(self, card_number):
        self.card_number = card_number
    
    def pay(self, amount):
        return f"Paid ${amount} using Credit Card ending in {self.card_number[-4:]}"


class PayPalStrategy(PaymentStrategy):
    def __init__(self, email):
        self.email = email
    
    def pay(self, amount):
        return f"Paid ${amount} using PayPal account {self.email}"


class CryptoStrategy(PaymentStrategy):
    def __init__(self, wallet_address):
        self.wallet_address = wallet_address
    
    def pay(self, amount):
        return f"Paid ${amount} using Crypto wallet {self.wallet_address[:8]}..."


class ShoppingCart:
    def __init__(self, payment_strategy):
        self.payment_strategy = payment_strategy
    
    def set_payment_strategy(self, payment_strategy):
        self.payment_strategy = payment_strategy
    
    def checkout(self, amount):
        return self.payment_strategy.pay(amount)


# Usage
if __name__ == "__main__":
    cart = ShoppingCart(CreditCardStrategy('1234567812345678'))
    print(cart.checkout(100))
    
    cart.set_payment_strategy(PayPalStrategy('user@example.com'))
    print(cart.checkout(50))
    
    cart.set_payment_strategy(CryptoStrategy('0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb'))
    print(cart.checkout(75))