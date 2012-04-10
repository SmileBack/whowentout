require 'spec_helper'

describe '/api/v1/users', :type => :api do

  before(:each) do
    user = User.create!(:first_name => 'Joe', :last_name => 'Schmoe', :email => 'joe@schmoe.com')
    user = User.create!(:first_name => 'Doe', :last_name => 'Whoa', :email => 'doe@woo.com')
  end

  let(:url) { '/api/v1/users' }

  context "should get users" do

    it 'json' do
      get "#{url}.json"

      users = JSON.parse(last_response.body)
      users.length.should == 2

      puts users.inspect

      users.any? { |u| u['first_name'] == 'Joe' }.should be_true
      users.any? { |u| u['email'] == 'doe@woo.com' }.should be_true
    end

  end

  context "should create user" do

  end

end
