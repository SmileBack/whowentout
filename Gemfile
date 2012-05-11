source 'http://rubygems.org'

gem 'rails', '3.1.0'

# Bundle edge Rails instead:
# gem 'rails',     :git => 'git://github.com/rails/rails.git'

# Gems used only for assets and not required
# in production environments by default.
group :assets do
  gem 'sass-rails', "  ~> 3.1.0"
  gem 'coffee-rails', "~> 3.1.0"
  gem 'uglifier'
end

gem 'state_machine'

gem 'jquery-rails'
gem 'execjs'
gem 'therubyracer'

# Use unicorn as the web server
# gem 'unicorn'

# Deploy with Capistrano
# gem 'capistrano'

# To use debugger
gem 'debugger', :group => [:test, :development]

gem 'grape'
gem 'koala'
gem 'geocoder'
gem 'acts-as-taggable-on', '~> 2.2.2'
gem 'formtastic', '~> 2.1.1'
gem 'activeadmin'
gem 'resque'

# Databases
group :test, :development do
  gem 'sqlite3'
end

group :production do
  gem 'pg'
end

# Testing
group :test, :development do
  gem 'rspec-rails', '~> 2.6'
end

group :test do
  gem 'rspec'
  gem 'webmock'
  gem 'vcr'
end

group :test do
  # Pretty printed test output
  gem 'turn', :require => false
  gem 'factory_girl_rails'
end
