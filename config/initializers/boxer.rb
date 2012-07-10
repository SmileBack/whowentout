Boxer.configure do |config|

end

# Load all of our boxes
unless Rails.env.test?
  Dir[File.join(Rails.root, 'lib', 'boxer', '**', '*.rb')].each do |f|
    require_dependency f
  end
end
