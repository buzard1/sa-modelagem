from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
import time, random

# Configurações do navegador
options = webdriver.ChromeOptions()
options.add_argument("--disable-notifications")
options.add_argument("--disable-infobars")
options.add_argument("--disable-extensions")

driver = webdriver.Chrome(options=options)
wait = WebDriverWait(driver, 10)

# ====== LOGIN ======
driver.get("http://localhost:8080/sa-modelagem/index.php")
print("🌐 Acessando página de login...")
time.sleep(3)

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

# Entrar
print("👉 Clicando no botão de login...")
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Login realizado com sucesso!")
time.sleep(3)

# ====== IR PARA PÁGINA DE ESTOQUE ======
print("👉 Indo para a tela de Estoque...")
link_estoque = wait.until(
    EC.element_to_be_clickable((By.CSS_SELECTOR, ".sidebar .menu li a[href='estoque.php']"))
)
link_estoque.click()
print("✅ Página de Estoque aberta!")
time.sleep(3)

# ====== PREENCHER FORMULÁRIO DE CADASTRO DE PRODUTO ======
produtos = [
    "Tela Samsung Galaxy S20", "Bateria iPhone 11", "Câmera traseira Xiaomi", 
    "Conector de carga USB-C", "Fone de ouvido Bluetooth", "Película de vidro temperado",
    "Capa protetora silicone", "Carregador turbo 25W", "Cartão memória 64GB",
    "Display OLED iPhone 12"
]

# Selecionar dados aleatórios
nome_produto = random.choice(produtos)
quantidade = random.randint(1, 100)
valor = round(random.uniform(10, 500), 2)

# Nome do produto
campo_produto = wait.until(EC.visibility_of_element_located((By.ID, "produto")))
campo_produto.send_keys(nome_produto)
print(f"[ESTOQUE] Produto digitado: {nome_produto}")
time.sleep(2)

# Quantidade
campo_quantidade = driver.find_element(By.ID, "quantidade")
campo_quantidade.send_keys(str(quantidade))
print(f"[ESTOQUE] Quantidade digitada: {quantidade}")
time.sleep(2)

# Valor
campo_valor = driver.find_element(By.ID, "valor")
campo_valor.send_keys(str(valor))
print(f"[ESTOQUE] Valor digitado: R$ {valor}")
time.sleep(2)

# Fornecedor (select pelo CNPJ existente no <select>)
select_fornecedor = Select(driver.find_element(By.ID, "fornecedor"))
opcoes = select_fornecedor.options

# Escolher uma opção aleatória (ignorar a primeira se for "Selecione...")
opcao_escolhida = random.choice(opcoes[1:]) if len(opcoes) > 1 else opcoes[0]
select_fornecedor.select_by_value(opcao_escolhida.get_attribute("value"))

print(f"[ESTOQUE] Fornecedor selecionado: {opcao_escolhida.text} (CNPJ: {opcao_escolhida.get_attribute('value')})")
time.sleep(2)

# ====== SALVAR ======
print("👉 Clicando no botão CADASTRAR PRODUTO...")
driver.find_element(By.CSS_SELECTOR, "form button[type='submit']").click()
print("✅ Produto cadastrado com sucesso!")

time.sleep(5)
print("✅ Script finalizado com sucesso!")

time.sleep(3)
driver.quit()
