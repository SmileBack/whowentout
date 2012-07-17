FactoryGirl.define do

  factory :user do |f|
    f.sequence(:first_name) { |n| "Guyfirst #{n}" }
    f.sequence(:last_name) { |n| "Guylast #{n}" }
    facebook_id 1234
    gender "M"
    status "online"
    email { first_name.downcase + '@example.com' }
  end

  factory :place do |f|
    f.sequence(:name) { |n| "Place #{n}" }
    f.latitude 45
    f.longitude 75
  end

  factory :region do |f|
    f.sequence(:name) { |n| "Region #{n}" }
  end

end
