class SyncFromFacebookWorker
  @queue = :sync_facebook

  def self.perform(options)
    user_id = options['user_id']
    fields = options['fields']

    user = User.find(user_id)
    user.sync_from_facebook fields
  end

end