ActiveAdmin.register Region do

  form do |f|
    f.inputs "Details" do
      f.input :name
      f.input :point_list, :as => :text, :input_html => { :class => 'autogrow', :rows => 10 }
    end

    f.buttons
  end

  show do |region|
    attributes_table do
      row :id
      row :name
      row :points

      row :map do
        render :partial => 'map', :locals => {
            :settings => {
                :zoom => 12,
                :center => [40.76338, -73.96682],
                :points => region.points
            }
        }
      end

    end
    active_admin_comments
  end

end
