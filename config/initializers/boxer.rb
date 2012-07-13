class Boxer

  def self.ship_all(type, objects, *args)
    objects.map { |o| ship(type, o, *args) }
  end

end

Boxer.configure do |config|
  config.box_includes = []
end

# Load all of our boxes
unless Rails.env.test?
  Dir[File.join(Rails.root, 'lib', 'boxer', '**', '*.rb')].each do |f|
    require_dependency f
  end
end
