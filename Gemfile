source 'http://rubygems.org'

gem 'rails', '3.1.0'
gem 'rake', '0.8.7' # heroku issue where rake is built into ruby 1.9.2+ so we gotta use this gem

gem 'thin'

# Bundle edge Rails instead:
# gem 'rails',     :git => 'git://github.com/rails/rails.git'

# Gems used only for assets and not required
# in production environments by default.
gem 'sass-rails', "  ~> 3.1.0"
group :assets do
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
gem 'pry', :group => [:test, :development]
gem 'pry-nav', :group => [:test, :development]

gem 'grape'
gem 'boxer'
gem 'koala'
gem 'geocoder'
gem 'acts-as-taggable-on', '~> 2.2.2'
gem 'formtastic', '~> 2.1.1'
gem 'activeadmin'
gem 'resque'
gem 'mechanize', :require => false
gem 'urbanairship', :git => 'git://github.com/groupon/urbanairship.git'

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
  gem 'timecop'
end

group :test do
  # Pretty printed test output
  gem 'turn', :require => false
  gem 'factory_girl_rails'
end
