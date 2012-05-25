class SmileGame < ActiveRecord::Base

  belongs_to :sender, :class_name => 'User'
  belongs_to :receiver, :class_name => 'User'

  has_many :choices, :class_name => 'SmileGameChoice', :order => 'position ASC'

  state_machine :status, :initial => :open do
  end

  def has_guesses_left?
    false
  end

  def self.shuffle(arr)
    arr.shuffle
  end

  def self.define_shuffler(&block)
    define_singleton_method(:shuffle, &block)
  end

  def self.create_for_user(user, sender, number_of_choices = 12)
    game = SmileGame.create(sender_id: sender.id, receiver_id: user.id)

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
    available_users = available_incorrect_user_choices
    raise "No available choices remaining" if available_users.empty?

    random_index = Kernel.rand(available_users.count)
    available_users.limit(1).offset(random_index).first
  end

  def available_incorrect_user_choices
    join_condition = "INNER JOIN smile_games
                        ON NOT(users.id = smile_games.receiver_id AND smile_games.sender_id = #{self.receiver.id})
                        AND NOT(users.id = smile_games.sender_id AND smile_games.receiver_id = #{self.receiver.id})"

    available_choices = User.joins(join_condition)

    not_game_receiver = User.arel_table[:id].eq(self.receiver.id).not
    available_choices = available_choices.where(not_game_receiver)

    unless choices.empty?
      chosen_user_ids = choices.map { |c| c.user.id }
      not_existing_choice = User.arel_table[:id].not_in(chosen_user_ids)
      available_choices = available_choices.where(not_existing_choice)
    end

    return available_choices.order('id ASC')
  end

  def shuffle!
    numbers = (0...choices.count).to_a
    shuffled_numbers = SmileGame.shuffle(numbers)
    index = 0
    choices.each do |c|
      c.position = shuffled_numbers[index]
      c.save
      index += 1
    end
    choices.reload
  end

end
