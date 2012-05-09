class Checkin < ActiveRecord::Base
  belongs_to :user, :inverse_of => :checkins
  belongs_to :place
end
