class GWUDirectoryImporter
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

    q = Query.create :value => query, :num_results => num_results

    students.each do |student_data|
      student = save_student(student_data[:name], student_data[:email])
      q.students << student
    end

    trigger :on_save_students, students
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
