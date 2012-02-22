class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries

  attr_accessible :name, :email

  serialize :data, Hash
  
  validates :name, :presence => true
end
