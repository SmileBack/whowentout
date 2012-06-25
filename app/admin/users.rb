ActiveAdmin.register User do

  index do
    column :first_name
    column :last_name
    column :email
    column :status
    column :created_at
    column :latitude
    column :longitude

    default_actions
  end

end
