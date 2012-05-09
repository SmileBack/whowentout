class UserLocation < ActiveRecord::Base
  geocoded_by nil
  belongs_to :user, :inverse_of => :locations
end