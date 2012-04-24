module ApiHelper
  include Rack::Test::Methods

  def app
    Rails.application
  end

  def get_json(url, options = {}, session = {})
    get url, options, 'rack.session' => sign_in_parameters.merge(session)
    JSON.parse(last_response.body)
  end

  def post_json(url, options = {}, session = {})
    post url, options, 'rack.session' => sign_in_parameters.merge(session)
    JSON.parse(last_response.body)
  end

  def sign_in
    @signed_in = true
  end

  def sign_out
    @signed_in = false
  end

  def sign_in_parameters
    if @signed_in == true
      {
        'user_id' => venkats_id,
        'token' => venkats_token
      }
    else
      {}
    end
  end

  def venkats_id
    User.where(:first_name => 'Venkat', :last_name => 'Dinavahi').first.id
  end
  def venkats_token
    "AAACEdEose0cBAFqkOkxzEMtEc2J1sVZBgAZBDDrdFkSHtCb99t65Bb8Jcfyoh2mUejoZBGj5vz7YWnZBg68YTHCPhzXPZC5A99X5TgPdpdwZDZD"
  end


  def dans_id
    User.where(:first_name => 'Dan', :last_name => 'Berenholtz').first.id
  end
  def dans_token
    "AAACEdEose0cBAMDvdZAuWsPp4uT7CDzbafEOGnalVQvpNVR5FPDDKvSZCtRl2ZA6jCwoBDe5TAFgGSdu7PWGxxgZARWgPZBwXiStGxlkZCfgZDZD"
  end

end


