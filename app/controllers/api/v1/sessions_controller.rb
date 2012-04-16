class Api::V1::SessionsController < Api::V1::BaseController

  def index
    puts session.inspect
    if session[:user_id].nil?
      @user = nil
    else
      @user = User.find(session[:user_id])
    end
  end

  def create
    if params[:token].nil?
      render :json => {
          :success => false,
          :message => "Must provide Facebook OAuth token."
      }
    else
      @user = User.find_by_token(params[:token])

      puts @user.inspect

      session[:user_id] = @user.id
      session[:token] = params[:token]
    end
  end

  def destroy
    session[:user_id] = nil
    session[:token] = nil

    render :json => {:success => true}
  end

end
