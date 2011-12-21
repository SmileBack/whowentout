class DirectoryImporterLogger

  def initialize(importer)
    @importer = importer

    subscribe
  end

  private

  def subscribe
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