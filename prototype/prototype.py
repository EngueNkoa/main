# Decorator Pattern - Adds new functionality to objects dynamically

class Coffee:
    def cost(self):
        return 5
    
    def description(self):
        return 'Simple coffee'


class CoffeeDecorator:
    def __init__(self, coffee):
        self._coffee = coffee
    
    def cost(self):
        return self._coffee.cost()
    
    def description(self):
        return self._coffee.description()


class MilkDecorator(CoffeeDecorator):
    def cost(self):
        return self._coffee.cost() + 1.5
    
    def description(self):
        return self._coffee.description() + ', milk'


class SugarDecorator(CoffeeDecorator):
    def cost(self):
        return self._coffee.cost() + 0.5
    
    def description(self):
        return self._coffee.description() + ', sugar'


class WhippedCreamDecorator(CoffeeDecorator):
    def cost(self):
        return self._coffee.cost() + 2
    
    def description(self):
        return self._coffee.description() + ', whipped cream'


# Usage
if __name__ == "__main__":
    my_coffee = Coffee()
    print(f"{my_coffee.description()} - ${my_coffee.cost()}")
    
    my_coffee = MilkDecorator(my_coffee)
    print(f"{my_coffee.description()} - ${my_coffee.cost()}")
    
    my_coffee = SugarDecorator(my_coffee)
    print(f"{my_coffee.description()} - ${my_coffee.cost()}")
    
    my_coffee = WhippedCreamDecorator(my_coffee)
    print(f"{my_coffee.description()} - ${my_coffee.cost()}")