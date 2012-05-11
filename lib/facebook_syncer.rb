module FacebookSyncer

  def self.sync_from_facebook(user, fields)
    fields = [fields] unless fields.is_a?(Array)
    fields.each do |f|
      syncer_class = get_syncer_class(f)
      syncer = syncer_class.new
      syncer.sync(user, user.facebook_token)
    end
  end

  def self.require_syncer(name)
    require Rails.root.join('lib', 'facebook_syncer', "#{name}_syncer")
  end

  def self.get_syncer_class(name)
    name = name.to_s
    require_syncer(name)
    FacebookSyncer.const_get(name.camelize + 'Syncer')
  end

end