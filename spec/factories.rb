FactoryGirl.define do
  factory :user do
    first_name "Venkat"
    last_name "Dinavahi"
    gender "M"
    email { first_name.downcase + '@example.com' }
  end
end