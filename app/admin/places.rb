ActiveAdmin.register Place do

  index do
    column :name
    column "Type", :tag_list

    column :address

    default_actions
  end

  form do |f|
    f.inputs "Details" do
      f.input :name
      f.input :tag_list

      f.input :phone_number
      f.input :address
      f.input :latitude
      f.input :longitude
      f.input :details
    end

    f.buttons
  end
end
