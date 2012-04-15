require 'spec_helper'

describe User do

  let(:fb_access_token) do
    "AAACm1V7H288BAEsdcHrcsMGDmjqTKLchLIFHu2Jh0HeZA0XZBvyuyZCvOGEFr9mMp4oiMFr2Yw6Nq1leTf9FasRZBKKtXUMZD"
  end

  describe "find_by_token" do

    it "should return the proper fields" do
      user = User.find_by_token(fb_access_token)

      user.facebook_id.should == 776200121
      user.first_name.should == 'Venkat'
      user.last_name.should == 'Dinavahi'
      user.email.should == 'ven@stanford.edu'
      user.gender.should == 'M'
      user.birthday.should == Date.new(1988, 10, 6)
    end

    it "shouldnt return duplicate users" do
      user_a = User.find_by_token(fb_access_token)
      user_b = User.find_by_token(fb_access_token)

      user_a.should == user_b
      user_a.id.should == user_b.id
    end

  end

end