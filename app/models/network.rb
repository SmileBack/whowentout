class Network < ActiveRecord::Base
  has_many :network_memberships
  has_many :users, :through => :network_memberships
end