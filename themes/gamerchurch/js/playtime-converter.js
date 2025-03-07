// Used on the single game post template for converting playtime in minutes into a human-readable format

document.addEventListener("DOMContentLoaded", () => {
  // Function to convert minutes (and optional seconds) into 00D:00H:00M:00S format
  function formatMinutesToTime(value) {
    const minutes = Math.floor(value);
    const seconds = Math.round((value - minutes) * 100);

    const days = Math.floor(minutes / (24 * 60));
    const hours = Math.floor((minutes % (24 * 60)) / 60);
    const remainingMinutes = minutes % 60;

    return `${String(days).padStart(2, '0')}D:${String(hours).padStart(2, '0')}H:${String(remainingMinutes).padStart(2, '0')}M:${String(seconds).padStart(2, '0')}S`;
  }

  // Locate all elements with the class name .convert-min
  const elements = document.querySelectorAll(".convert-min");

  elements.forEach((element) => {
    // Loop through child nodes and find plain text containing the number
    const textNode = Array.from(element.childNodes).find(
      (node) => node.nodeType === Node.TEXT_NODE && /^\d+(\.\d{1,2})?$/.test(node.textContent.trim())
    );

    if (textNode) {
      // Parse the numeric value and convert it to the desired format
      const numericValue = parseFloat(textNode.textContent.trim());
      const formattedTime = formatMinutesToTime(numericValue);

      // Create the <time> element
      const timeElement = document.createElement("time");
      timeElement.textContent = formattedTime;

      // Replace the text node with the <time> element
      textNode.replaceWith(timeElement);
    }
  });
});
