from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from faker import Faker
import random
import time

fake = Faker("pt_BR")
driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)  # espera máxima de 10s

# ====== LOGIN ======
driver.get("http://localhost:8080/sa-modelagem/login.php")
wait.until(EC.presence_of_element_located((By.ID, "email"))).send_keys("admin@admin")
driver.find_element(By.ID, "senha").send_keys("123")
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

# ====== IR PARA FORNECEDORES ======
link_fornecedores = wait.until(
    EC.element_to_be_clickable((By.CSS_SELECTOR, ".sidebar .menu li a[href='fornecedor.php']"))
)
link_fornecedores.click()

# ====== CLICAR EM CADASTRAR ======
botao_cadastrar = wait.until(
    EC.element_to_be_clickable((By.ID, "btn-cadastrar"))
)
botao_cadastrar.click()

# ====== PREENCHER FORMULÁRIO ======
nome_fornecedor = fake.company()
telefone = fake.msisdn()[0:11]
email = fake.company_email()
endereco = fake.address().replace("\n", ", ")
cnpj = "".join([str(random.randint(0, 9)) for _ in range(14)])

# Espera cada campo individualmente antes de preencher
wait.until(EC.visibility_of_element_located((By.NAME, "nome_fornecedor"))).send_keys(nome_fornecedor)
wait.until(EC.visibility_of_element_located((By.NAME, "telefone"))).send_keys(telefone)
wait.until(EC.visibility_of_element_located((By.NAME, "email"))).send_keys(email)
wait.until(EC.visibility_of_element_located((By.NAME, "endereco"))).send_keys(endereco)
wait.until(EC.visibility_of_element_located((By.NAME, "cnpj"))).send_keys(cnpj)

time.sleep(1)  # pausa para visualização

# ====== ENVIAR FORMULÁRIO ======
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Fornecedor cadastrado com sucesso!")

time.sleep(5)  # espera para visualizar resultado
driver.quit()
