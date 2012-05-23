class SmileGameChoice < ActiveRecord::Base

  belongs_to :user

  state_machine :status, :initial => :inactive do

    event :mark_as_can_match do
      transition :inactive => :will_match
    end

    event :mark_as_cannot_match do
      transition :inactive => :wont_match
    end

    event :guess do
      transition :will_match => :matched
      transition :wont_match => :didnt_match
    end

  end

end