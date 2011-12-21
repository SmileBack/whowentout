class DirectoryImporter
  include EventPublisher

  event :on_skip
  event :on_save_students
  event :on_save_student

  def initialize(dir)
    @dir = dir
  end

  def import(query)
    if query_exists?(query)
      trigger :on_skip, query
      return nil
    end

    students = @dir.search(query)

    save(query, @dir.num_reported_results, students)
  end

  def save(query, num_results, students)
    if query_exists?(query)
      raise "Query #{query} already exists."
    end

    Query.transaction do
      q = Query.create :value => query, :num_results => num_results, :status => 'incomplete'

      students.each do |student_data|
        student = save_student(student_data[:name], student_data[:email])
        q.students << student
      end

      q.update_attributes :status => 'complete'

      trigger :on_save_students, students
    end
  end

  def destroy_query(query)
    query = Query.where(:value => query).first
    unless query.nil?
      query.students.each { |s| s.destroy }
    end
    query.destroy
  end

  def save_student(name, email)
    student = Student.find_by_email(email)
    if student.nil?
      student = Student.create(:name => name, :email => email)
      trigger :on_save_student, student
    end
    return student
  end

  def query_exists?(query)
    return Query.exists?(:value => query)
  end

  def student_exists?(email)
    return Student.exists?(:email => email)
  end

end
