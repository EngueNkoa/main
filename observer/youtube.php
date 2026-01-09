<?php
interface Observer {
    public function update(string $video);
}

class AnalyticsService implements Observer {
    public function update(string $video) {
        echo "📈 Analytics: Logged upload of video '{$video}'.\n";
        echo "updates the view count and engagement metrics.\n";
        echo "----------------------------------------\n";
    }
}

class Subscriber implements Observer {
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function update(string $video) {
        echo "{$this->name} has been notified about new video: {$video}\n";
    }
}

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify(string $video);
}

class YouTubeChannel implements Subject {
    private array $subscribers = [];

    public function attach(Observer $observer) {
        $this->subscribers[] = $observer;
    }

    public function detach(Observer $observer) {
        foreach ($this->subscribers as $key => $sub) {
            if ($sub === $observer) {
                unset($this->subscribers[$key]);
            }
        }
    }

    public function notify(string $video) {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->update($video);
        }
    }

    public function uploadVideo(string $title) {
        echo "📢 New Video Uploaded: {$title}\n";
        $this->notify($title);
      
    }
}

$channel = new YouTubeChannel();

$john = new Subscriber("John");
$mary = new Subscriber("Mary");
$alex = new Subscriber("Alex");

$channel->attach($john);
$channel->attach($mary);
$channel->attach($alex);

$channel->uploadVideo("Observer Pattern in PHP");
