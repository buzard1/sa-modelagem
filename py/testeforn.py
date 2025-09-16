from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from faker import Faker
import time

# Configurações do navegador
options = webdriver.ChromeOptions()
options.add_argument("--disable-notifications")
options.add_argument("--disable-infobars")
options.add_argument("--disable-extensions")

driver = webdriver.Chrome(options=options)
wait = WebDriverWait(driver, 10)

# Gerador de dados falsos
fake = Faker("pt_BR")

# ====== LOGIN ======
driver.get("http://localhost:8080/sa-modelagem/index.php")
print("🌐 Acessando página de login...")
time.sleep(3)  # pausa para você ver a tela de login

# Email
campo_email = wait.until(EC.presence_of_element_located((By.ID, "email")))
campo_email.send_keys("admin@admin")
print(f"[LOGIN] Email digitado: admin@admin")
time.sleep(2)

# Senha
campo_senha = driver.find_element(By.ID, "senha")
campo_senha.send_keys("123")
print(f"[LOGIN] Senha digitada: 123")
time.sleep(2)

print("👉 Clicando no botão de login...")
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Login realizado com sucesso!")
time.sleep(3)  # pausa para visualizar redirecionamento

# ====== IR PARA FORNECEDORES ======
print("👉 Indo para a tela de fornecedores...")
link_fornecedores = wait.until(
    EC.element_to_be_clickable((By.CSS_SELECTOR, ".sidebar .menu li a[href='fornecedor.php']"))
)
link_fornecedores.click()
print("✅ Página de fornecedores aberta!")
time.sleep(3)  # pausa para visualizar a tela

# ====== ESPERAR FORMULÁRIO CARREGAR ======
wait.until(EC.presence_of_element_located((By.ID, "nome_fornecedor")))
print("📋 Formulário de cadastro carregado.")

# ====== PREENCHER FORMULÁRIO ======
nome_fornecedor = fake.company()
telefone = fake.msisdn()[0:11]
email = fake.company_email()

campo_nome = driver.find_element(By.ID, "nome_fornecedor")
campo_nome.send_keys(nome_fornecedor)
print(f"[CADASTRO] Nome do fornecedor digitado: {nome_fornecedor}")
time.sleep(2)

campo_tel = driver.find_element(By.ID, "telefone")
campo_tel.send_keys(telefone)
print(f"[CADASTRO] Telefone digitado: {telefone}")
time.sleep(2)

campo_email = driver.find_element(By.ID, "email")
campo_email.send_keys(email)
print(f"[CADASTRO] Email digitado: {email}")
time.sleep(2)

# ====== ENVIAR FORMULÁRIO ======
print("👉 Clicando no botão CADASTRAR fornecedor...")
driver.find_element(By.CSS_SELECTOR, "form button[type='submit']").click()
print("✅ Fornecedor cadastrado com sucesso!")

time.sleep(5)  # espera para visualizar resultado
driver.quit()
