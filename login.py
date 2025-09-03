from selenium import webdriver
from selenium.webdriver.common.by import By
import time

# Configuração do WebDriver (Chrome)
driver = webdriver.Chrome()

# ====== ETAPA 1: Abrir página de login ======
driver.get("http://localhost:8080/sa-modelagem/login.php")  # ajuste a URL conforme seu ambiente
print("✅ Página de login aberta")

# ====== ETAPA 2: Preencher e-mail ======
email_input = driver.find_element(By.ID, "email")
email_input.send_keys("admin@admin")
print("✅ E-mail preenchido")

time.sleep(2)

# ====== ETAPA 3: Preencher senha ======
senha_input = driver.find_element(By.ID, "senha")
senha_input.send_keys("123")
print("✅ Senha preenchida")

time.sleep(2)

# ====== ETAPA 4: Clicar no botão 'Entrar' ======
login_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
login_button.click()
print("✅ Botão de login clicado")

# Aguarda um pouco para visualizar se o login deu certo
time.sleep(5)

# Fecha navegador
driver.quit()
