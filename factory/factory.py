# Factory Pattern - Creates objects without specifying exact class

class Car:
    def __init__(self, model):
        self.model = model
        self.type = 'Car'
    
    def drive(self):
        return f"Driving a {self.model} car"


class Truck:
    def __init__(self, model):
        self.model = model
        self.type = 'Truck'
    
    def drive(self):
        return f"Driving a {self.model} truck"


class Motorcycle:
    def __init__(self, model):
        self.model = model
        self.type = 'Motorcycle'
    
    def drive(self):
        return f"Riding a {self.model} motorcycle"


class VehicleFactory:
    @staticmethod
    def create_vehicle(vehicle_type, model):
        vehicles = {
            'car': Car,
            'truck': Truck,
            'motorcycle': Motorcycle
        }
        
        vehicle_class = vehicles.get(vehicle_type.lower())
        if vehicle_class:
            return vehicle_class(model)
        else:
            raise ValueError(f"Unknown vehicle type: {vehicle_type}")


# Usage
if __name__ == "__main__":
    factory = VehicleFactory()
    
    car = factory.create_vehicle('car', 'Tesla Model 3')
    truck = factory.create_vehicle('truck', 'Ford F-150')
    bike = factory.create_vehicle('motorcycle', 'Harley Davidson')
    
    print(car.drive())
    print(truck.drive())
    print(bike.drive())