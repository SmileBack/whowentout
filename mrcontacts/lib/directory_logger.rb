class DirectoryLogger

  def initialize(directory)
    @directory = directory

    subscribe
  end

  private

  def subscribe
    @directory.subscribe :on_login do |username, password|
      puts "logged in as #{username}"
    end

    @directory.subscribe :on_search do |keywords, num_results, pages|
      puts "searched for #{keywords} and got #{num_results} results with #{pages} pages"
    end

    @directory.subscribe :on_load_page do |keywords, page|
      puts "loaded page #{page} for '#{keywords}'"
    end
  end

end