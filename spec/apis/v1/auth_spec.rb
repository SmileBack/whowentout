require 'spec_helper'

describe "/auth", :type => :api do

  let(:token) { "AAACm1V7H288BAEsdcHrcsMGDmjqTKLchLIFHu2Jh0HeZA0XZBvyuyZCvOGEFr9mMp4oiMFr2Yw6Nq1leTf9FasRZBKKtXUMZD" }

  it "should start off as logged out" do
    get 'api/v1/auth/current-user.json'

    response = JSON.parse(last_response.body)
    response['success'].should == false
  end

  describe "/login" do
    it "should work with a valid token" do
      post "api/v1/auth/login.json", :token => token

      response = JSON.parse(last_response.body)
      response['success'].should == true

      get "api/v1/auth/current-user.json"
      response = JSON.parse(last_response.body)
      response['first_name'].should == 'Venkat'
    end
  end

  describe "/logout" do
    it "should affect current-user" do
      post 'api/v1/auth/logout.json'
      response = JSON.parse(last_response.body)
      response['success'].should == true

      get "api/v1/auth/current-user.json"
      response = JSON.parse(last_response.body)
      response['success'].should == false
    end
  end

end