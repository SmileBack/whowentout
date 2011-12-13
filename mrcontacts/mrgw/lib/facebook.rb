require 'rubygems'
require 'fb_graph'

def get_facebook_ids(emails)
  facebook_ids = {}
  #auth = FbGraph::Auth.new('161054327279516', '8b1446580556993a34880a831ee36856')
  ven_token = '161054327279516|9fa9b03352a60587e7c04f29.1-776200121|_7ZNMzYiaJs3vEGn2taGcRenApM'
  dan_token = '161054327279516|40a239af03df79b6bdc54b9d.1-8100231|GTocc5_SANI6WuPeA1dKKZ5Nm3I'
  emails.each do |e|
    users = FbGraph::User.search(e, :access_token => ven_token)
    if users.length > 0
      facebook_ids[e] = users.first.identifier
    end
  end
  return facebook_ids
end

emails = ['cassies@gwmail.gwu.edu', 'danb1@stanford.edu', 'Andrew Chester']
puts "getting facebook ids"
puts get_facebook_ids(emails).inspect

