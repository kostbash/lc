class BaseCase


  def self.unauthorized driver
    begin
      logout = driver.find_element(:link_text, 'Выход')
      print "Выходим из аккаунта..."
      logout.click
        begin
          driver.find_element(:link_text, 'Войдите')
          puts "   Успешно!"
        rescue

        end
    rescue

    end
  end

  def self.authorized driver
    begin
      is_logined = driver.find_element(:link_text, 'Войдите')
      if is_logined
        print "Авторизовываемся..."
        is_logined.click
        driver.find_element(:name, 'LoginForm[username]').send_keys  USER_NAME
        driver.find_element(:name, 'LoginForm[password]').send_keys  USER_PASSWORD
        driver.find_element(:name, 'LoginForm[password]').submit
        begin
          driver.find_element(:id, 'logout')
          puts "   Успешно!"
        rescue
          puts "Провал!"
          puts "Ошибка авторизации! Проверьте правильность логина и пароля. Дальнейшее проведение тестов невозможно."
          driver.quit

        end
      end
    rescue

    end
  end
end