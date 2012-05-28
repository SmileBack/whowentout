class SmileGame < ActiveRecord::Base

  belongs_to :sender, :class_name => 'User'
  belongs_to :receiver, :class_name => 'User'
  belongs_to :match, :class_name => 'User'
  belongs_to :origin, :class_name => 'SmileGame'

  has_many :choices, :class_name => 'SmileGameChoice', :order => 'position ASC'

  state_machine :status, :initial => :open do
    event :mark_as_matched do
      transition :open => :match
    end

    event :mark_as_didnt_match do
      transition :open => :no_match
    end

    state :open do
      def open?
        true
      end
    end

    state :match, :no_match do
      def open?
        false
      end
    end

  end

  def has_guesses_remaining?
    guesses_remaining > 0
  end

  def total_guesses_allowed
    3
  end

  def guesses_remaining
    total_guesses_allowed - guesses_used
  end

  def guesses_used
    choices.where(status: 'no_match').count
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

  def self.created_today
    where("smile_games.created_at >= ? AND smile_games.created_at < ?", Date.today, Date.tomorrow)
  end

  def self.direct
    direct_condition = arel_table[:origin_id].eq(nil)
    where(direct_condition)
  end

  def guess(choice, number_of_choices = 12)
    success = choice.guess
    return if success == false

    if choice.status == 'match'
      self.match = choice.user
      self.mark_as_matched
      save
    elsif choice.status == 'no_match'
      game = receiver.start_smile_game_with(choice.user, number_of_choices)
      game.update_attribute(:origin_id, self.id)

      self.mark_as_didnt_match if has_guesses_remaining? == false
    end
  end

  def add_incorrect_choice
    user = draw_incorrect_user
    choice = choices.create(user: user, status: 'wont_match')
    choice.mark_as_cannot_match
  end

  def add_correct_choice(user)
    choice = choices.create(user: user, status: 'will_match')
    choice.mark_as_can_match
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
