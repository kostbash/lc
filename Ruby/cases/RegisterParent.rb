class RegisterParent
  def go_test driver
    error = []
    puts "Начало теста: регистрация родителя"

    BaseCase.unauthorized driver

    driver.navigate.to DOMAIN_NAME

    begin
      register_button = driver.find_element(:link_text, 'Зарегистрируйтесь')
      register_button.click

      driver.find_element(:id, 'role-parent').click
      driver.find_element(:id, 'Users_email').send_keys Time.now.to_i.to_s+"@somehost.com"
      driver.find_element(:id, 'submit-reg').click

        puts 'Успешно!'


    rescue
      puts "Ошибка! Подробности в логах"
    end

    return error
  end

end
