from selenium import webdriver
from selenium.webdriver.common.by import By
from faker import Faker
import time
import random

# Criador de dados fake
fake = Faker("pt_BR")

# Configuração do WebDriver (Chrome)
driver = webdriver.Chrome()

# ====== ETAPA 1: Login ======
driver.get("http://localhost:8080/sa-modelagem/index.php")  # ajuste a URL
print("✅ Página de login aberta")
time.sleep(2)

driver.find_element(By.ID, "email").send_keys("admin@admin")
time.sleep(1)

driver.find_element(By.ID, "senha").send_keys("123")
time.sleep(1)

driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Login realizado")
time.sleep(3)  # aguarda redirecionamento

# ====== ETAPA 3: Gerar dados aleatórios ======
nome = fake.name()
telefone = fake.msisdn()[0:11]
email = fake.email()
endereco = fake.address().replace("\n", ", ")
cpf = "".join([str(random.randint(0, 9)) for _ in range(11)])

print("📌 Dados gerados para cadastro:")
print("Nome:", nome)
print("Telefone:", telefone)
print("E-mail:", email)
print("Endereço:", endereco)
print("CPF:", cpf)

# ====== ETAPA 4: Preencher formulário (mais lento) ======
driver.find_element(By.ID, "nome").send_keys(nome)
time.sleep(1)

driver.find_element(By.ID, "telefone").send_keys(telefone)
time.sleep(1)

driver.find_element(By.ID, "email").send_keys(email)
time.sleep(1)

driver.find_element(By.ID, "endereco").send_keys(endereco)
time.sleep(1)

driver.find_element(By.ID, "cpf").send_keys(cpf)
time.sleep(1)

# ====== ETAPA 5: Enviar formulário ======
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Cliente cadastrado")

# Espera para visualizar resultado
time.sleep(5)

driver.quit()
