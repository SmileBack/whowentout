class ImportLogger

  def initialize(directory, importer)
    @directory = directory
    @importer = importer

    subscribe_to_directory
    subscribe_to_importer
  end

  private

  def subscribe_to_directory
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

  def subscribe_to_importer
    @importer.subscribe :on_skip_query do |query|
      puts "skipped query #{query}"
    end

    @importer.subscribe :on_save_student do |student|
      puts "saved #{student.name}, #{student.email}"
    end

    @importer.subscribe :on_skip_student do |student|
      puts "skipped #{student.name}, #{student.email}"
    end

    @importer.subscribe :on_save_students do |students|
      puts "saved #{students.length} students to db"
    end
  end

end