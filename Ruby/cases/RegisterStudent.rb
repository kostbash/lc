class RegisterStudent
  def go_test driver
    error = []
    puts "Начало теста: регистрация ученика"

    BaseCase.unauthorized driver

    driver.navigate.to DOMAIN_NAME

    begin
      register_button = driver.find_element(:link_text, 'Зарегистрируйтесь')
        register_button.click

      user_name = 'test_user_'+Time.now.to_i.to_s
      driver.find_element(:id, 'Users_username').send_keys user_name
      driver.find_elements( :tag_name => "option" ).find do |option|
        option.text == 'Имя любимого животного'
      end.click
      driver.find_element(:id, 'Users_recovery_answer').send_keys Time.now.to_i.to_s
      driver.find_element(:id, 'submit-reg').click

        begin
          driver.find_element(:id, 'logout')
            puts 'Успешно!'
        rescue
          puts "Произошла ошибка. Детали в логах д"
          error = ['Ошибка при регистрации ученика. Ученик небыл зарегистрирован']
        end

    rescue
      puts "Произошла ошибка. Детали в логах"
      error = ['Не удалось заполнить форму при регистрации ученика']
    end

    return error
  end
end