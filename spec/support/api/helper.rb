module ApiHelper
  include Rack::Test::Methods

  def app
    Rails.application
  end

  def woo
    'wooyea'
  end

end


