class Student < ActiveRecord::Base
  has_and_belongs_to_many :queries

  attr_accessible :name, :email

  serialize :data, Hash
  
  validates :name, :presence => true
  validates :email, :presence => true, :uniqueness => true
end
