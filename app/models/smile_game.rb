class SmileGame < ActiveRecord::Base

  belongs_to :sender, :class_name => 'User'
  belongs_to :receiver, :class_name => 'User'

  has_many :choices, :class_name => 'SmileGameChoice', :order => 'position ASC'

  state_machine :status, :initial => :open do
  end

  def has_guesses_left?
    false
  end

  def self.create_for_user(user, sender, number_of_choices = 12)
    game = SmileGame.create(status: 'created', sender_id: sender.id, receiver_id: user.id)

    game.add_correct_choice(sender)
    (number_of_choices - 1).times { game.add_incorrect_choice }

    game.shuffle!

    return game
  end

  def add_incorrect_choice
    user = draw_incorrect_user
    choices.create(user: user, status: 'wont_match')
  end

  def add_correct_choice(user)
    choices.create(user: user, status: 'will_match')
  end

  def draw_incorrect_user
    join_condition = "INNER JOIN smile_games
                        ON NOT(
                          users.id = smile_games.receiver_id AND smile_games.sender_id = #{self.receiver.id}
                          OR users.id = smile_games.sender_id AND smile_games.receiver_id = #{self.receiver.id}
                        )"
    not_sender = User.arel_table[:id].not_in([self.sender.id])

    available_choices = User.joins(join_condition).where(not_sender)

    raise "No availiable choices remaining" if available_choices.empty?

    random_index = rand(available_choices.count)

    available_choices.limit(1).offset(random_index).first
  end

  def shuffle!

  end

end
