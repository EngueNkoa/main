# Observer Pattern - Notifies multiple objects about state changes

class Subject:
    def __init__(self):
        self._observers = []
    
    def subscribe(self, observer):
        self._observers.append(observer)
    
    def unsubscribe(self, observer):
        self._observers.remove(observer)
    
    def notify(self, data):
        for observer in self._observers:
            observer.update(data)


class Observer:
    def __init__(self, name):
        self.name = name
    
    def update(self, data):
        print(f"{self.name} received: {data}")


# Usage
if __name__ == "__main__":
    news_agency = Subject()
    
    subscriber1 = Observer('John')
    subscriber2 = Observer('Sarah')
    subscriber3 = Observer('Mike')
    
    news_agency.subscribe(subscriber1)
    news_agency.subscribe(subscriber2)
    news_agency.subscribe(subscriber3)
    
    news_agency.notify('Breaking News: New design patterns released!')
    
    print("\n--- Sarah unsubscribed ---\n")
    news_agency.unsubscribe(subscriber2)
    
    news_agency.notify('Update: Observer pattern is awesome!')