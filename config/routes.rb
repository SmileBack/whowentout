require_relative '../lib/api'
require 'resque/server'

Wwo::Application.routes.draw do

  ActiveAdmin.routes(self)

  devise_for :admin_users, ActiveAdmin::Devise.config

  resources :users

  mount WWOApi => '/'
  mount Resque::Server => "/admin/jobs"

end
