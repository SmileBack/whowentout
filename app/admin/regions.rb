ActiveAdmin.register Region do

  form do |f|
    f.inputs "Details" do
      f.input :name
      f.input :point_list, :as => :text, :input_html => { :class => 'autogrow', :rows => 10 }
    end

    f.buttons
  end

end
