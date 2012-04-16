module ApiHelper
  include Rack::Test::Methods

  def app
    Rails.application
  end

  def get_json(url, options={})
    get(url, options)
    JSON.parse(last_response.body)
  end

  def post_json(url, options={})
    post(url, options)
    JSON.parse(last_response.body)
  end

end


